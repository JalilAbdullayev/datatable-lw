<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component {
    use WithPagination;

    #[Url()]
    public int $perPage = 5;
    #[Url(history: true)]
    public string $search = '';
    #[Url('role', history: true)]
    public $admin = '';
    #[Url(history: true)]
    public string $sortBy = 'created_at';
    #[Url(history: true)]
    public string $sortDir = 'DESC';

    public function updatedSearch() {
        $this->resetPage();
    }

    public function delete(User $user): void {
        $user->delete();
    }

    public function setSortBy(string $sortByField) {
        if($this->sortBy === $sortByField) {
            $this->sortDir = $this->sortDir === 'ASC' ? 'DESC' : 'ASC';
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = 'DESC';
    }

    public function render(): View {
        $users = User::search($this->search)->when($this->admin !== '', function($query) {
            $query->whereIsAdmin($this->admin);
        })->orderBy($this->sortBy, $this->sortDir)->paginate($this->perPage);
        return view('livewire.users-table', ['users' => $users]);
    }
}
