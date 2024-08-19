<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Settings;
use App\Models\PistaTipo;

class IndexComponent  extends Component
{
    use LivewireAlert;

    public $trackTypes = [];
    public $newTrackType;

    public function mount()
    {
        $this->trackTypes = PistaTipo::all()->pluck('nombre')->toArray();
    }

    public function render()
    {
        dd('render');
        return view('livewire.settings.index-component');
    }

    public function getListeners()
    {
        return [
            'confirmed'
        ];
    }

    public function confirmed()
    {
        $this->saveTrackTypes();
        return redirect()->route('settings.index');
    }

    public function addTrackType()
    {
        if ($this->newTrackType) {
            $this->trackTypes[] = $this->newTrackType;
            $this->newTrackType = '';
        }
    }

    public function removeTrackType($index)
    {
        unset($this->trackTypes[$index]);
        $this->trackTypes = array_values($this->trackTypes); // Reindexar el array
    }

    protected function saveTrackTypes()
    {
        PistaTipo::truncate();
        foreach ($this->trackTypes as $type) {
            PistaTipo::create(['nombre' => $type]);
        }
    }
}
