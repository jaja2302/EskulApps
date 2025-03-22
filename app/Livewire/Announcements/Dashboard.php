<?php

namespace App\Livewire\Announcements;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Announcements;
use App\Models\Eskul;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Dashboard extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $isModalOpen = false;
    public $announcement;
    public $announcementId;
    public $title;
    public $content;
    public $eskul_id;
    public $is_important = false;
    public $publish_at;
    public $expire_at;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = ['delete'];

    protected function rules()
    {
        return [
            'title' => 'required|min:5|max:255',
            'content' => 'required|min:10',
            'eskul_id' => 'nullable|exists:eskuls,id',
            'is_important' => 'boolean',
            'publish_at' => 'nullable|date',
            'expire_at' => 'nullable|date|after_or_equal:publish_at',
        ];
    }

    public function mount()
    {
        $this->publish_at = now()->format('Y-m-d');
        $this->expire_at = now()->addMonths(1)->format('Y-m-d');
    }

    public function render()
    {
          
        $announcements = Announcements::query()
            ->with(['eskul', 'createdBy'])
            ->when($this->search, function ($query) {
                return $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
            
        $eskuls = Eskul::all();
        
        return view('livewire.announcements.dashboard', [
            'announcements' => $announcements,
            'eskuls' => $eskuls,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Announcements::class);
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        $announcement = Announcements::findOrFail($id);
        $this->authorize('update', $announcement);
        
        $this->announcementId = $id;
        $this->title = $announcement->title;
        $this->content = $announcement->content;
        $this->eskul_id = $announcement->eskul_id;
        $this->is_important = $announcement->is_important;
        $this->publish_at = $announcement->publish_at ? $announcement->publish_at->format('Y-m-d') : now()->format('Y-m-d');
        $this->expire_at = $announcement->expire_at ? $announcement->expire_at->format('Y-m-d') : now()->addMonths(1)->format('Y-m-d');
        
        $this->openModal();
    }

    public function store()
    {
        $this->validate();
        
        if ($this->announcementId) {
            $announcement = Announcements::find($this->announcementId);
            $this->authorize('update', $announcement);
            
            $announcement->update([
                'title' => $this->title,
                'content' => $this->content,
                'eskul_id' => $this->eskul_id,
                'is_important' => $this->is_important,
                'publish_at' => $this->publish_at,
                'expire_at' => $this->expire_at,
            ]);
            
            session()->flash('message', 'Pengumuman berhasil diperbarui.');
        } else {
            $this->authorize('create', Announcements::class);
            
            Announcements::create([
                'title' => $this->title,
                'content' => $this->content,
                'eskul_id' => $this->eskul_id,
                'is_important' => $this->is_important,
                'publish_at' => $this->publish_at,
                'expire_at' => $this->expire_at,
                'created_by' => auth()->id(),
            ]);
            
            session()->flash('message', 'Pengumuman berhasil dibuat.');
        }
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $announcement = Announcements::findOrFail($id);
        $this->authorize('delete', $announcement);
        
        $this->announcementId = $id;
        $this->dispatch('confirm-deletion', [
            'id' => $id,
            'title' => 'Hapus Pengumuman',
            'message' => 'Apakah Anda yakin ingin menghapus pengumuman ini?',
        ]);
    }

    public function delete()
    {
        $announcement = Announcements::findOrFail($this->announcementId);
        $this->authorize('delete', $announcement);
        
        $announcement->delete();
        session()->flash('message', 'Pengumuman berhasil dihapus.');
        $this->resetInputFields();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    private function resetInputFields()
    {
        $this->announcementId = null;
        $this->title = '';
        $this->content = '';
        $this->eskul_id = null;
        $this->is_important = false;
        $this->publish_at = now()->format('Y-m-d');
        $this->expire_at = now()->addMonths(1)->format('Y-m-d');
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }
}
