
function publications() {
  const POSTS_PER_LOAD = 12;
  let currentIndex = 0;
  let isLoading = false;

  const posts = window.POSTS;

  const $postList = $('#post-list');
  const $loader = $('#loader');

  if (!$postList.length) return;

  loadMorePosts();

  function loadMorePosts() {
    if (isLoading) return;
    isLoading = true;

    $loader.show();

    const nextPosts = posts.slice(
      currentIndex,
      currentIndex + POSTS_PER_LOAD
    );

    if (!nextPosts.length) {
      $loader.hide();
      $(window).off('scroll', handleScroll);
      return;
    }

    const $items = [];

    $.each(nextPosts, function (index, post) {
      const $item = $(`
      <article class="post">
        <div class="thumb">
          <a href="${post.link}" target="_blank" rel="noopener">
            <img src=" ${post.logo ? post.logo : "/assets/images/thumb.webp"} " />
            <div class="source">
            Source : ${post.source ? post.source : ""}
            </div>
          </a>
        </div>
        <p class="description">${post.desc ? post.desc : ""}</p>
        <a href="${post.link}" class="btn btn-sm"  data-title="View Full Article" target="_blank" rel="noopener">
          <div class="text">
            <span class="front">View Full Article</span>
            <span class="back">View Full Article</span>
          </div>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </article>
    `);

      $items.push($item);
      $postList.append($item);
    });

    // GSAP reveal animation
    gsap.from($items, {
      opacity: 0,
      y: 40,
      duration: 0.6,
      stagger: 0.08,
      ease: "power2.out"
    });

    currentIndex += POSTS_PER_LOAD;
    isLoading = false;
    $loader.hide();
  }

  function handleScroll() {
    if (isLoading) return;

    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const windowHeight = window.innerHeight;
    const docHeight = document.documentElement.scrollHeight;

    const distanceFromBottom = docHeight - (scrollTop + windowHeight);

    if (distanceFromBottom < 800) {
      loadMorePosts();
    }
  }

  $(window).on('scroll', handleScroll);
}
