<?php

namespace App\Livewire\Admin;

use App\Exports\ArticlesExport;
use App\Livewire\Forms\ArticleForm;
use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class Articles extends Component
{
    use WithPagination;
    use WireToast;

    public $search;
    public $isOpen = false,
        $showArticle = false,
        $isOpenDelete = false;
    public $itemId;
    public ArticleForm $form;
    public ?Article $article;

    public function render()
    {
        $articles = Article::query()
            ->where(function ($query) {
                $query
                    ->where('titulo', 'like', '%' . $this->search . '%');
            })
            ->latest('id')
            ->paginate(10);

        return view('livewire.admin.articles', compact('articles'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function edit(Article $article)
    {
        $this->resetForm();
        $this->isOpen = true;
        $this->itemId = $article->id;
        $this->article = $article;
        $this->form->fill($article);
    }

    public function store()
    {
        $this->validate();
        $articleData = $this->form->toArray();
        if (!isset($this->article->id)) {
            Article::create($articleData);
            toast()->success('Usuario creado correctamente', 'Mensaje de éxito')->push();
        } else {
            $this->article->update($articleData);
            toast()->success('Usuario actualizado correctamente', 'Mensaje de éxito')->push();
        }
        $this->closeModals();
    }

    public function deleteItem($id)
    {
        $this->itemId = $id;
        $this->isOpenDelete = true;
    }

    public function delete()
    {
        Article::find($this->itemId)->delete();
        toast()->success('Usuario eliminado correctamente', 'Mensaje de éxito')->push();
        $this->reset('isOpenDelete', 'itemId');
    }

    public function showArticleDetail(Article $article)
    {
        $this->showArticle = true;
        $this->edit($article);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function closeModals()
    {
        $this->isOpen = false;
        $this->showArticle = false;
        $this->isOpenDelete = false;
    }

    private function resetForm()
    {
        $this->form->reset();
        $this->reset(['article', 'itemId']);
        $this->resetValidation();
    }

    public function createPDF()
    {
        $total = Article::count();
        $user = Auth::user()->name;
        $date = date('Y-m-d');
        $hour = date('H:i:s');
        $articles = Article::get();
        $pdf = FacadePdf::loadView('reports/pdf_articles', compact('articles', 'total', 'user', 'date', 'hour'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('reporte-de-articulos.pdf'); //desacarga automaticamente
        // return $pdf->stream('reports/pdf_articles'); //abre en una pestaña como pdf
    }


    public function createExcel()
    {
        return Excel::download(new ArticlesExport(), 'reporte-de-articulos.xlsx');
    }

    public function createCSV()
    {
        return Excel::download(new ArticlesExport(), 'reporte-de-articulos.csv', \Maatwebsite\Excel\Excel::CSV);
    }

}
