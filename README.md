This is a simple PHP code that we use, more or less customised, to display news from the October's Rainlab Blog.

There are 2 files included in this repository:

code.php - code that should be pasted on October's page "Code" tab.

markup.htm - markup example using bootstrap and some custom css


Let's explain code.php :

```
	function onInit() {
		$posts = RainLab\Blog\Models\Post::whereHas('categories', function($q) {
			$q->where('name', '=', 'Highlight');
			})->orderBy('published_at', 'desc')->take(3)->get()->toArray();
```

This lines does two basic things: they take 3 latest posts from blog's category Highlight and put them into array. You can change "take(3)" if you want to take more posts.


```
	$extractedPosts = array_map(function ($post){
	        $extractedPost = [];
	        $extractedPost['title'] = $post['title'];
	        $postContent = strip_tags($post['content'], '<a><b><i><u>');
```

Here the post conversion starts. Content is being stripped from tags.


```
	$title = $post['title'];
	        $extractedPost['titles'] = ((strlen($title) > 20) ? mb_substr($title, 0, mb_strpos($title, ' ', 17)) . '...' : $title);
```

Here the title is stripped if too long to fit the column. You can customise 20 and 17 values with your own.

```
	$extractedPost['short'] = ((strlen($postContent) > 130) ? mb_substr($postContent, 0, mb_strpos($postContent, ' ', 122)) . '...' : $postContent);
	$extractedPost['featured_image'] = (isset($post['featured_images'][0]) ? $post['featured_images'][0] : null);
	$extractedPost['slug'] = $post['slug'];
```

Here the content is stripped to become excerpt of 122 signs and "..." if total length of content is above 130 signs. Feel free to customise those values. Also featured image is attached here and slug variable defined.

```
		return $extractedPost;
	}, $posts);

	$this['extractedPosts'] = $extractedPosts;
	}
```

This returns $extractedPosts, ready to be used in your markup



Now let's quickly explain markup.htm:


```
	{% for post in extractedPosts %}
	<div class="col-sm-4 col-md-4">
		<h4>{{ post.titles }}</h4>
		<div class="col-md-4 col-sm-4">
		<div class="img-featured"><img src="{{post.featured_image.path}}" class="img-responsive" alt="news-image"></div>
		</div>
		<div class="col-md-8 col-sm-8">
			<p>{{post.short | raw}}</p>
		</div>
	<a href="/post/{{post.slug}}" class="btn btn-primary">Więcej</a>
</div>
	{% endfor %}
```

First there is a 'for' loop to go trough all extractedPosts. Then col-md-4 bootstrap column and finally the content of excerpt is being included as

{{post.titles}} is a shortened title of the post.
{{post.featured.image.path}} is a path to featured image
{{post.short | raw }} is content stripped to given length
{{post.slug}} is a slug of the post. To make this link work you should have "post/:slug" page with blogPost component.