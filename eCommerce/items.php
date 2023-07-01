<?php
    ob_start();
    session_start();
    $pageTitle = 'Show Items';
    include 'init.php';

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; //check the number of userid for the user that i get from database
    // by session['ID'] and echo it in the url and get it by get method
    //select all the data about the userid
    $stmt = $con->prepare("SELECT
                                items.*,
                                categories.Name As category_name,
                                users.Username
                            FROM
                                items
                            INNER JOIN
                                categories
                            ON
                                categories.ID = items.Cat_ID
                            INNER JOIN 
                                users
                            ON 
                                users.UserID = items.Member_ID
                            WHERE 
                                Item_ID = ?
                                AND Approve = 1"); 
    $stmt->execute(array($itemid));

    $count = $stmt->rowCount();

    if($count > 0) {
    //fetch"جلب" all the record from database
    $item = $stmt->fetch();  //array contain the record data

?>
    <h1 class="text-center"><?php $item['Name'] ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-responsive center-block" src="img.jpg" alt=""/>
            </div>
            <div class="col-md-9 item-info">
                <h2><?php echo $item['Name']; ?></h2>
                <p><?php echo $item['Description']; ?></p>
                <ul class="list-unstyled ">
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span><?php echo $item['Add_Date']; ?></span>
                    </li>
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span>Price: $<?php echo $item['Price']; ?></span>
                    </li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span>Made In: <?php echo $item['Country_Made'] ;?></span>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Category: <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ; ?>"> <?php echo $item['category_name'] ;?></a></span>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Added By: <a href="#"> <?php echo $item['Username'] ;?></a></span>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span >Tags:</span>
                         <?php 
                            $allTags = explode(",", $item['tags']);
                            foreach($allTags as $tag) {
                                $tag = str_replace(' ', '', $tag);
                                $lowertag = strtolower($tag);
                                if (! empty($tag)) {
                                echo "<a class='tag' href='tags.php?name={$lowertag}'>" . $tag . "</a>";
                                }
                            }
                         ?>
                    </li>
                </ul>
            </div>
        </div>
        
        <hr class="custom-hr">
        <?php if (isset($_SESSION['user'])) {?>
        <div class="row">
            <div class="col-md-offset-3">
                <div class="add-comment">
                    <h3>Add Your Comment</h3>
                    <form action="<?php $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID']  ?>" method="POST">
                        <textarea name="comment" class="form-control box" required></textarea>
                        <input class="btn btn-primary" type="submit" value="Add Comment"/>

                    </form>
                    <?php 
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            $itemid = $item['Item_ID'];
                            $userid = $_SESSION['uid'];

                            if(!empty($comment)) {
                                $stmt = $con->prepare("INSERT INTO 
                                    comments(comment, status, comment_date, item_id, user_id)
                                VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid) ");
                                $stmt->execute(array(
                                    ':zcomment' => $comment,
                                    ':zitemid' => $itemid,
                                    ':zuserid' => $userid
                                ));

                                if ($stmt) {
                                    echo '<div class="alert alert-success">Comment Added</div>';
                                }
                            }
                        }
                    ?>

                </div>
            </div>
        </div>
                    <?php } else {
                        echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';
                        }?>

        <hr class="custom-hr">

            
        <?php 
        $stmt = $con->prepare("SELECT
            comments.*, users.Username AS Member
        FROM
            comments
        INNER JOIN
            users
        ON
            users.UserID = comments.user_id
        WHERE
            item_id = ?
        AND 
            status = 1
        ORDER BY 
        c_id DESC
            ");
        //execute the statement
        $stmt->execute(array($item['Item_ID']));
        $comments = $stmt->fetchAll();

        ?>
        <?php
            foreach($comments as $comment) { ?>
            <div class="comment-box">
                <div class="row">
                        <div class="col-sm-2 text-center">
                            <img class="img-responsive center-block img-thumbnail img-circle" src="img.jpg" alt="" />
                            <?php echo $comment['Member'] ?> 
                        </div>
                        <div class="col-sm-10">
                            <div class="lead"> <?php echo $comment['comment'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
                
        <?php } ?>
        
<?php
    }else {
        echo 'There is no such id or waiting approval item';
    }
    include $tpl . 'footer.php';    
    ob_flush_end();
?>