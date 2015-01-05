<?php
// NEST SNAPPER

// ----------------------------------------------------------------------------


// Includes
require_once 'config.php';
require_once 'src/snapchat.php';

// ----------------------------------------------------------------------------

// Go!

// Log in:
$snapchat = new Snapchat(NEST_SNAPPER_USERNAME, NEST_SNAPPER_PASSWORD);

// Get your feed:
$snaps = $snapchat->getSnaps();

// ----------------------------------------------------------------------------

// Listing

header('Content-type: text/plain');

echo 'Looking for new snaps to forward. ';

foreach($snaps as $snap){

    if($snap->status == 1 && $snap->recipient == NEST_SNAPPER_USERNAME){
        // Download a snap:
        $data = $snapchat->getMedia($snap->id);

        // Mark the snap as viewed:
        $snapchat->markSnapViewed($snap->id);

        // Send
        echo 'Sending snap from ' . $snap->sender . ' to ' . implode(', ', $nesters) . '...';
        $id = $snapchat->upload(
            Snapchat::MEDIA_IMAGE,
            $data
        );
        $snapchat->send($id, $nesters, 8);
    }
}

// ----------------------------------------------------------------------------
