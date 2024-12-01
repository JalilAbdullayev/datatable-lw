<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component {
    use WithPagination;

    public int $perPage = 5;
    public string $search = '';
    public $admin = '';

    public function delete(User $user): void {
        $user->delete();
    }

    public function render(): View {
        $users = User::search($this->search)->when($this->admin !== '', function($query) {
            $query->whereIsAdmin($this->admin);
        })->paginate($this->perPage);
        return view('livewire.users-table', ['users' => $users]);
    }
}
