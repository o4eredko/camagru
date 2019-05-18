<section id="title">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="main__title">Your Best Social Network</h1>
                <p class="main__subtitle">It is like instagram, but much better...</p>
                <a href="#" class="button signup__button" data-toggle-id="registration"><i class="fas fa-lock"></i>Sign up now</a>
            </div>
        </div>
    </div>
</section>
<section id="main">
    <div class="container">
        <div class="posts slider">
            <?php foreach ($posts as $post): ?>
            <div class="col-md-3 col-12">
                <div class="post" data-id="<?= $post["id"] ?>">
                    <a href="post?id=<?= $post["id"] ?>" class="post__img">
                        <img src="<?= $post["img"] ?>" alt="">
                    </a>
                    <div class="post__content">
                        <h4 class="post__title"><?= $post["title"] ?></h4>
                        <hr>
                        <p class="post__owner">by <a href="#"> <?= $post["owner"] ?></a></p>
                    </div>
                    <div class="post__stat">
						<?php if (!isset($_SESSION["user"])): ?>
                            <a href="#" class="post__like" data-toggle-id="login">
                        <?php elseif (in_array($post["id"], $likedPosts)): ?>
                            <a href="#" class="post__like active" data-post-id="<?= $post["id"] ?>">
                        <?php else: ?>
                            <a href="#" class="post__like" data-post-id="<?= $post["id"] ?>">
						<?php endif; ?>
                            <i class="fa fa-thumbs-up"></i>
                            <?= $post["likes"] ?>
                        </a>
                        <a href="post?id= <?= $post["id"] ?>" class="post__comment"
                            <?php if (!isset($_SESSION["user"])): ?>
                                data-toggle-id="login"
                            <?php endif; ?>
                        >
                            <i class="fas fa-comment comment"></i><?= $post["comments"] ?>
                        </a>
                        <?php if (isset($_SESSION["user"]) && $post["owner"] == $_SESSION["user"]): ?>
                            <span class="post__del">
                                <i class="fas fa-trash-alt" data-post-id="<?= $post["id"] ?>"></i>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
