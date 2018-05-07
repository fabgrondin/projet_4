<?php
// Class loading
require_once(__DIR__.'/../model/PostManager.php');
require_once(__DIR__.'/../model/CommentManager.php');
require_once(__DIR__ .'/../model/MembersManager.php');

function adminAccess()
{
    require(__DIR__ .'/../view/backend/admin.php');
}

function disconnect()
{
    session_destroy();
    header('location:index.php');
    exit();
}

function createPost()
{
    require(__DIR__.'/../view/backend/createPost.php');
}

function addPost()
{
    if (isset($_POST['title']) && isset($_POST['content'])) {
        if (!empty($_POST['title']) && !empty($_POST['content'])) {
            $newTitle = htmlspecialchars($_POST['title']);
            $newContent = $_POST['content'];

            $postManager = new PostManager();
            $result=$postManager->add($newTitle, $newContent);

            if ($result !== 0) {
                $_SESSION['message'] = "L'article a bien été publié !";
                header('location:index.php');
                exit();
            } else {
                $_SESSION['articleTitle'] = $newTitle;
                $_SESSION['articleContent'] = $newContent;
                throw new \Exception("L'article n'a pas pu être publié.", 1);
            }
        } else {
            $_SESSION['articleTitle'] = $_POST['title'];
            $_SESSION['articleContent'] = $_POST['content'];

            throw new \Exception("Tous les champs ne sont pas remplis", 1);
        }
    } else {
        throw new \Exception("Erreur lors du traitement.", 1);
    }
}

function adminListPosts()
{
    $postManager = new PostManager();
    $posts = $postManager->getPosts();

    require(__DIR__. '/../view/backend/listPosts.php');
}

function modify()
{
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $postManager = new PostManager();
        $post = $postManager->getPost($id);

        if ($post) {
            require(__DIR__ . '/../view/backend/modifyPost.php');
        } else {
            throw new \Exception("Aucun billet à ce numéro", 1);
        }
    } else {
        throw new \Exception("Aucun identifiant de billet envoyé.", 1);
    }
}

function update()
{
    if (isset($_GET['id'])) {
        if (isset($_POST['title']) && isset($_POST['content'])) {
            if (!empty($_POST['title']) && !empty($_POST['content'])) {
                $id = htmlspecialchars($_GET['id']);
                $newTitle = htmlspecialchars($_POST['title']);
                $newContent = $_POST['content'];
                $postManager = new PostManager();

                if ($postManager->exists($id)) {
                    $result = $postManager->updatePost($id, $newTitle, $newContent);
                    if ($result !== 0) {
                        $_SESSION['message'] = "L'article a bien été modifié !";
                        header('location:index.php');
                        exit();
                    } else {
                        $_SESSION['articleTitle'] = $newTitle;
                        $_SESSION['articleContent'] = $newContent;
                        throw new \Exception("L'article n'a pas pu être publié.", 1);
                    }
                } else {
                    throw new \Exception("Aucun article avec cet identifiant", 1);
                }
            } else {
                $_SESSION['articleTitle'] = $_POST['title'];
                $_SESSION['articleContent'] = $_POST['content'];

                throw new \Exception("Tous les champs ne sont pas remplis", 1);
            }
        } else {
            throw new \Exception("Erreur lors du traitement.", 1);
        }
    } else {
        throw new \Exception("Aucun identifiant de billet envoyé.", 1);
    }
}

function deletePost()
{
    if (isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $postManager=new PostManager();

        if ($postManager->exists($id)) {
            $result = $postManager->deletePost($id);
            if ($result !== 0) {
                $_SESSION['message'] = "L'article a bien été supprimé !";
                header('location:index.php');
                exit();
            } else {
                throw new \Exception("L'article n'a pas pu être supprimé.", 1);
            }
        } else {
            throw new \Exception("Aucun article avec cet identifiant", 1);
        }
    } else {
        throw new \Exception("Aucun identifiant de billet envoyé.", 1);
    }
}
