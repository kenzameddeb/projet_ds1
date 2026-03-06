<?php
class VoteController {
    private $voteModel; 

    public function __construct($db) {
        $this->voteModel = new Vote($db);
    }

    public function create() {
        $submission_id = $_POST['submission_id'];
        $user_id       = $_SESSION['user']['id']; 

        if ($this->voteModel->checkVoteExists($submission_id, $user_id)) {
            header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $submission_id . "&error=already_voted");
            exit;
        }

        $this->voteModel->create($submission_id, $user_id);
        header("Location: /projet_ds1/index.php?page=challenge_show&id=" . $submission_id . "&success=voted");
        exit;
    }
}
