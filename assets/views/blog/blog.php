<a href="/blog">Блоги</a> / <a href="/blog/blog?act=new&amp;cid=<?=$cats['cats_id']?>">Написать</a><br /><br />

<img src="/images/img/open_dir.gif" alt="image" /> <b><?=$cats['cats_name']?></b> (Статей: <?=$cats['cats_count']?>)

<?php if (is_admin()): ?>
	(<a href="/admin/blog?act=blog&amp;cid=<?=$cats['cats_id']?>&amp;start=<?=$start?>">Управление</a>)
<?php endif; ?>
<hr />

<?php foreach ($blogs as $data): ?>

	<div class="b">
		<img src="/images/img/edit.gif" alt="image" />
		<b><a href="/blog/blog?act=view&amp;id=<?=$data['blogs_id']?>"><?=$data['blogs_title']?></a></b> (<?=format_num($data['blogs_rating'])?>)
	</div>

	<div>
		Автор: <?=profile($data['blogs_user'])?> (<?=date_fixed($data['blogs_time'])?>)<br />
		Просмотров: <?=$data['blogs_read']?><br />
		<a href="/blog/blog?act=comments&amp;id=<?=$data['blogs_id']?>">Комментарии</a> (<?=$data['blogs_comments']?>)
		<a href="/blog/blog?act=end&amp;id=<?=$data['blogs_id']?>">&raquo;</a>
	</div>
<?php endforeach; ?>

<a href="/blog/top">Топ статей</a> /
<a href="/blog/tags">Облако тегов</a> /
<a href="/blog/search">Поиск</a> /
<a href="/blog/blog?act=blogs">Все статьи</a> /
<a href="/blog/blog?act=new&amp;cid=<?=$cats['cats_id']?>">Написать</a><br />
