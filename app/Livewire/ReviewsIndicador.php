<?php

namespace App\Livewire;

use App\Models\IndicadorAnio;
use App\Models\Review;
use Livewire\Component;

class ReviewsIndicador extends Component
{
    public IndicadorAnio $anio;

    public $open = false;
    public $rating = 0;
    public $comment = '';

    protected $rules = [
        'rating'  => 'required|integer|min:1|max:5',
        'comment' => 'required|min:3',
    ];

    public function mount(IndicadorAnio $anio)
    {
        $this->anio = $anio;
    }

    public function store()
    {
        $this->validate();

        $this->anio->reviews()->create([
            'rating'  => $this->rating,
            'comment' => $this->comment,
            'user_id' => auth()->id(),
        ]);
        $this->dispatch('reviewGuardada', anioId: $this->anio->id);

        $this->reset(['open', 'rating', 'comment']);
    }


    public function eliminar($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();
        $this->dispatch('$refresh');

        // 🔔 Mensaje opcional
        session()->flash('message', 'Reseña eliminada correctamente.');
    }

    public function render()
    {
        $ratingsCount = collect([1, 2, 3, 4, 5])->mapWithKeys(fn($i) => [
            $i => $this->anio->reviews->where('rating', $i)->count()
        ]);

        return view('livewire.reviews-indicador', compact('ratingsCount'));
    }
}
