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
                <div class="post">
                    <a href="post?id=<?= $post["id"] ?>">
                        <img src="<?= $post["img"] ?>" alt="" class="post__img">
                    </a>
                    <div class="post__content">
                        <h4 class="post__title"><?= $post["title"] ?></h4>
                        <hr>
                        <p class="post__owner">
                            by <a href="#"> <?= $post["owner"] ?></a>
                        </p>
                    </div>
                    <div class="post__stat">
                        <?php if (in_array($post["id"], $likedPosts)): ?>
                            <span class="post__like">
                                <i class="fa fa-thumbs-up like active" data-post-id="<?= $post["id"] ?>"></i>
                                <?= $post["likes"] ?>
                            </span>
						<?php else: ?>
                            <span class="post__like">
                                <i class="fa fa-thumbs-up like" data-post-id="<?= $post["id"] ?>"></i>
                                <?= $post["likes"] ?>
                            </span>
						<?php endif; ?>
                        <a href="post?id= <?= $post["id"] ?>" class="post__comment"><i class="fas fa-comment comment"></i><?= $post["comments"] ?></a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
