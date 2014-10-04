// Highlighted News for Slider
function onInit() {
    $posts = RainLab\Blog\Models\Post::whereHas('categories', function($q) {
	$q->where('name', '=', 'Highlight');
    })->orderBy('published_at', 'desc')->take(3)->get()->toArray();

    $extractedPosts = array_map(function ($post){
        $extractedPost = [];
        $extractedPost['title'] = $post['title'];
        $postContent = strip_tags($post['content'], '<a><b><i><u>');
        $title = $post['title'];
        $extractedPost['titles'] = ((strlen($title) > 20) ? mb_substr($title, 0, mb_strpos($title, ' ', 17)) . '...' : $title);
        $extractedPost['short'] = ((strlen($postContent) > 130) ? mb_substr($postContent, 0, mb_strpos($postContent, ' ', 122)) . '...' : $postContent);
        $extractedPost['featured_image'] = (isset($post['featured_images'][0]) ? $post['featured_images'][0] : null);
        $extractedPost['slug'] = $post['slug'];
               // echo "<pre>"; var_dump($extractedPost['featured_image']); echo "</pre>";
        return $extractedPost;
    }, $posts);
    
    $this['extractedPosts'] = $extractedPosts;
}