<?php if (false !== $latestBlog): ?>
<p class="latest">
Our Latest Blog Post <a href="blog/<?php echo $this->e($latestBlog['fileName']); ?>"><?php echo $this->e($latestBlog['title']); ?></a>
</p>
<p class="eduvpn">
In the Research &amp; Education Community? Try <a href="https://www.eduvpn.org/">eduVPN!</a>
</p>
<?php endif; ?>
