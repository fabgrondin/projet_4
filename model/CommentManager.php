<?php
require_once('Manager.php');
require_once('Comment.php');

class CommentManager extends Manager
{
    public function getComments($idPost)
    {
        $db = $this->dbConnect();
        $comments = [];

        $req = $db->prepare('SELECT id, post_id, author, comment, comment_date, DATE_FORMAT(comment_date, "%d/%m/%Y à %Hh%imin%ss") AS comment_date_fr FROM comments WHERE post_id=?');
        $req->execute(array($idPost));

        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = new Comment($data);
        }

        return $comments;
    }

    public function getAllComments()
    {
        $db = $this->dbConnect();
        $comments = [];

        $req = $db->query('SELECT id, post_id, author, comment, comment_date, DATE_FORMAT(comment_date, "%d/%m/%Y à %Hh%imin%ss") AS comment_date_fr, reported, reporting FROM comments');

        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = new Comment($data);
        }

        return $comments;
    }

    public function getComment($id)
    {
        $db = $this->dbConnect();

        $req = $db->query('SELECT * FROM comments WHERE id='. $id);
        $data = $req->fetch(PDO::FETCH_ASSOC);

        $comment = new Comment($data);
        return $comment;
    }

    public function postComment($postId, $author, $comment)
    {
        $db = $this->dbConnect();

        $req = $db->prepare('INSERT INTO comments(post_id, author, comment, comment_date) VALUES (?, ?, ?, NOW())');
        $affectedLines = $req->execute(array($postId, $author, $comment));

        return $affectedLines;
    }

    public function exists($postId)
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT COUNT(*) FROM comments WHERE post_id='. $postId);
        return (bool) $req->fetchColumn();
    }

    public function deleteComment($id)
    {
        $db = $this->dbConnect();

        $affectedLines = $db->exec('DELETE FROM comments WHERE id=' .$id);

        return $affectedLines;
    }

    public function deleteComments($postId)
    {
        $db = $this->dbConnect();

        $affectedLines = $db->exec('DELETE FROM comments WHERE post_id=' .$postId);

        return $affectedLines;
    }

    public function reportComment($id, $reporting)
    {
        $comment = $this->getComment($id);

        $reported = $comment->reported()+1;
        $reporting = $comment->reporting() . $reporting .' \n ';

        $db = $this->dbConnect();

        $req = $db->prepare('UPDATE comments SET reported = :reported, reporting=:reporting WHERE id=:id');
        $req->bindValue(':reported', $reported);
        $req->bindValue(':reporting', $reporting);
        $req->bindValue(':id', $id);

        $affectedLines = $req->execute();
        return $affectedLines;
    }

    public function getReportedComments()
    {
        $db = $this->dbConnect();
        $comments = [];

        $req = $db->query('SELECT id, post_id, author, comment, comment_date, DATE_FORMAT(comment_date, "%d/%m/%Y à %Hh%imin%ss") AS comment_date_fr, reported, reporting FROM comments WHERE reported>0 ORDER BY reported');

        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = new Comment($data);
        }

        return $comments;
    }

    public function getNonReportedComments()
    {
        $db = $this->dbConnect();
        $comments = [];

        $req = $db->query('SELECT id, post_id, author, comment, comment_date, DATE_FORMAT(comment_date, "%d/%m/%Y à %Hh%imin%ss") AS comment_date_fr FROM comments WHERE reported=0');

        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = new Comment($data);
        }

        return $comments;
    }
}
