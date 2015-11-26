<div class="row">

	<!-- Blog Post Content Column -->
	<div class="col-lg-8">

		<!-- Blog Post -->

		<!-- Title -->
		<h1><?= $page->title?></h1>

		<!-- Author -->
		<p class="lead">
			by <a href="#"><?= $page->author->name?></a>
		</p>

		<hr>

		<!-- Date/Time -->
		<p><span class="glyphicon glyphicon-time"></span> <?= $page->modified?></p>

		<hr>

		<!-- Preview Image -->
		<img class="img-responsive" src="http://placehold.it/900x300" alt="">

		<hr>

		<!-- Post Content -->
		<?= $page->text;?>

		<hr>

		<!-- Blog Comments -->

		<!-- Comments Form -->
		<div class="well">
			<h4>Leave a Comment:</h4>
			<form role="form">
				<div class="form-group">
					<textarea class="form-control" id='rootmessage' rows="3"></textarea>
				</div>
				<button type="submit" class="btn btn-primary sendroot">Submit</button>
			</form>
		</div>

		<hr>
        <div id="comments-data">
        <?php if(count($comments)>0):?>
        <?php foreach($comments as $k=>$comment):?>
            <div id="container_commet_<?=$comment->id?>">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?=$comment->author->name;?>
                            <small><?=$comment->modified;?></small>
                        </h4>
                        <div data-type="textarea" data-showbuttons="left" data-pk="<?=$comment->id?>" data-url="/site/postComment" id="comment_<?=$comment->id?>" class="content editable-manual"><?= $comment->text;?></div>
                        <div id="show_<?=$comment->id?>">
                            <?php if($childrens[$k]>0):?>
                                <a edit-id="<?=$comment->id?>" class="btn-sm btn-warning load-comment" href="#">Развернуть</a>
                            <?php endif;?>
                            <a edit-id="<?=$comment->id?>" class="btn-sm btn-primary answer-button">ответить</a>
                            <a edit-id="<?=$comment->id?>" class="btn-sm btn-success edit-button">редактировать</a>
                            <a edit-id="<?=$comment->id?>" class="btn-sm btn-danger delete-button">удалить</a>
                        </div>
                        <div id="postForm_<?=$comment->id?>"></div>
                        <div id="newComments_<?=$comment->id?>"></div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
        <?php endif;?>
        </div>
	</div>

	<!-- Blog Sidebar Widgets Column -->
	<div class="col-md-4">

		<!-- Blog Search Well -->
		<div class="well">
			<h4>Blog Search</h4>
			<div class="input-group">
				<input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
								<span class="glyphicon glyphicon-search"></span>
							</button>
                        </span>
			</div>
			<!-- /.input-group -->
		</div>

		<!-- Blog Categories Well -->
		<div class="well">
			<h4>Blog Categories</h4>
			<div class="row">
				<div class="col-lg-6">
					<ul class="list-unstyled">
						<li><a href="#">Category Name</a>
						</li>
						<li><a href="#">Category Name</a>
						</li>
						<li><a href="#">Category Name</a>
						</li>
						<li><a href="#">Category Name</a>
						</li>
					</ul>
				</div>
				<div class="col-lg-6">
					<ul class="list-unstyled">
						<li><a href="#">Category Name</a>
						</li>
						<li><a href="#">Category Name</a>
						</li>
						<li><a href="#">Category Name</a>
						</li>
						<li><a href="#">Category Name</a>
						</li>
					</ul>
				</div>
			</div>
			<!-- /.row -->
		</div>

		<!-- Side Widget Well -->
		<div class="well">
			<h4>Side Widget Well</h4>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
		</div>

	</div>

</div>

<script type="text/javascript">
    $(document).ready(function() {

        $.fn.editable.defaults.mode = 'inline';


        $('#comments-data').on("click", 'a.answer-button',function(e) {
            e.stopPropagation();
            e.preventDefault();
            var id = $(this).attr("edit-id");
            $('#commentForm').remove();
            $.tmpl( $('#commentFormTmpl'), {idForm:'commentForm', id: id}).appendTo('#postForm_'+id);
        });

        $('#comments-data').on("click", 'a.delete-button',function(e) {
            e.stopPropagation();
            e.preventDefault();
            if (confirm('Уверены, что хотите удалить?')) {
                var id = $(this).attr("edit-id");
                $.post("/site/deleteCommet", { id: id })
                    .done(function(data) {
                        if (data.success) {
                            $('#container_commet_'+id).remove()
                        }
                    });
            }
        });

        $('#comments-data').on("click", 'button.sendComment',function(e) {
            var id = $(this).attr("edit-id");
            e.stopPropagation();
            e.preventDefault();
            var mes = $('#commentMessage').val();
            var id = $(this).attr("edit-id");
            $.post("/site/addChildrenComment", { value: mes, pk: id  })
                .done(function(data) {
                    if (data.success) {
                        $('#commentForm').remove();
                        $.tmpl( $('#commentTmpl'), data.post).appendTo('#newComments_'+id);
                    }
                });
        });


        $('.sendroot').click(function(e) {
            e.stopPropagation();
            e.preventDefault();
            var mes = $('#rootmessage').val();
            $.post("/site/createNewPostComment", { value: mes })
                .done(function(data) {
                    if (data.success) {
                        $('#rootmessage').val('');
                        $.tmpl($('#commentTmpl'), data.post).appendTo('#comments-data');
                    }
                });
        });

        $('#comments-data').on("click", 'a.edit-button',function(e) {
            e.stopPropagation();
            var id = $(this).attr("edit-id");
            $('#comment_'+id).editable('toggle');
        });

        $('#comments-data').on("click", 'a.toggle',function(e) {
            e.stopPropagation();
            e.preventDefault();
            var el = $(this);
            var id = el.attr("edit-id");
            $('#newComments_'+id).toggle();
            el.html() == "Развернуть" ? el.html('Свернуть') : el.html('Развернуть');

        });

        $('#comments-data').on("click", 'a.load-comment',function(e) {
            e.stopPropagation();
            e.preventDefault();

            var el = $(this);
            var id = el.attr("edit-id");
            el.removeClass('load-comment').addClass('toggle').html('Свернуть');
            $.getJSON('site/getComments/'+id, function(data){

                $.each(data, function(key, val){
                    $.tmpl( $('#commentTmpl'), val).appendTo('#newComments_'+id);
                });
                $()
            });
        });

    });
</script>
<script id="commentTmpl" type="text/x-jquery-tmpl">
    <div id="container_commet_${id}">
    <div>
        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object" src="http://placehold.it/64x64" alt="">
            </a>
            <div class="media-body">
                <h4 class="media-heading">${author}
                    <small>${modified}</small>
                </h4>
                <div data-type="textarea" data-pk="${id}" data-url="/site/postComment" id="comment_${id}" class="content editable-manual">${text}</div>
                    <div id="show_${id}">
                    {{if children>0}}
                        <a edit-id="${id}" class="btn-sm btn-warning load-comment" href="#">Развернуть</a>
                    {{/if}}
                    <a edit-id="${id}" class="btn-sm btn-primary answer-button">ответить</a>
                    <a edit-id="${id}" class="btn-sm btn-success edit-button">редактировать</a>
                    <a edit-id="${id}" class="btn-sm btn-danger delete-button">удалить</a>
                </div>
                <div id="postForm_${id}"></div>
                <div id="newComments_${id}"></div>
            </div>
        </div>
    </div>
</script>

<script id="commentFormTmpl" type="text/x-jquery-tmpl">
    <div class="well" id="${idForm}">
        <h4>Leave a Comment:</h4>
        <form role="form">
            <div class="form-group">
                <textarea class="form-control" id="commentMessage" rows="3"></textarea>
            </div>
            <button edit-id="${id}" type="submit" class="btn btn-primary sendComment">Submit</button>
        </form>
    </div>
</script>