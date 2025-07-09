add_action( 'totalcontest/actions/contest/vote', 'debug_totalcontest_vote_hook', 10, 3 );

function debug_totalcontest_vote_hook( $contest, $submission, $context ) {
    // Log details to error log for testing
    error_log( '✅ Vote hook fired!' );
    error_log( 'Submission ID: ' . $submission->post->ID );
    error_log( 'Submission Author ID: ' . $submission->post->post_author );
    
    if ( isset( $context['user'] ) ) {
        error_log( 'Voting User ID: ' . $context['user'] );
    } else {
        error_log( 'Voting User: Guest (no user ID)' );
    }

    // Test MyCred point award
    if ( isset( $context['user'] ) && $context['user'] != $submission->post->post_author ) {
        my_cred_add(
            'vote_reward',
            $submission->post->post_author,
            5,
            'Received a vote on submission ID ' . $submission->post->ID,
            $submission->post->ID
        );
        error_log( '✅ MyCred points awarded.' );
    }
}

add_action( 'totalcontest/actions/contest/vote', 'award_points_to_submission_author', 10, 3 );

function award_points_to_submission_author( $contest, $submission, $context ) {
    // Get the user ID of the submission author
    $author_id = $submission->post->post_author;

    // Optional: prevent self-voting reward
    if ( isset( $context['user'] ) && $context['user'] == $author_id ) {
        return; // do not reward self-voting
    }

    // Award MyCred points
    my_cred_add(
        'vote_reward',           // Reference
        $author_id,              // User ID to award
        5,                       // Amount of points
        'Received a vote on submission: ' . get_the_title( $submission->post->ID ),
        $submission->post->ID   // Optional: related post ID
    );
}
