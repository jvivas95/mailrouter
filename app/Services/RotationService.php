<?php
namespace App\Services;

use App\Models\Recipient;
use App\Models\RotationState;

class RotationService
{

    public function getNextRecipient(): ?Recipient
    {
        // Get active recipients in order of their rotation index
        $recipients = Recipient::active()->get();

        if ($recipients->isEmpty()) {
            return null; // No active recipients
        }

        // Get the current rotation state
        $state = RotationState::firstOrCreate(
            ['id' => 1],
            ['current_index' => 0]
        );

        $idx = $state->current_index;

        // Reset index if it exceeds the number of recipients
        if ($idx >= $recipients->count()) {
            $idx = 0;
        }

        $recipient = $recipients[$idx];

        // Update the rotation state for the next call
        $state->update([
            'current_index' => ($idx + 1) % $recipients->count()
        ]);

        return $recipient;

    }


}
