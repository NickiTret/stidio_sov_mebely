@if (!$posts->isEmpty())
<section class="posts">
    <div class="container post-container">
        <h2><a href="posts.html">Наши <span class="mark"> новости и статьи</span></a></h2>
        <ul class="list-reset posts__list">
            @foreach ($posts as $post)
            <li class="posts__item">
                <div class="posts__item-top">
                    <img src="{{ $post->getImage() }}" alt="{{ $post->title }}">
                </div>
                <div class="posts__item-bottom">
                    <span class="posts__date">{{ $post->getImage() }}</span>
                    <h3 class="posts__title">{{ $post->title }}</h3>
                    <a href="#"> Читать
                        <svg class="posts__arrow">
                            <use xlink:href="img/sprite.svg#arrow"></use>
                        </svg>
                    </a>
                </div>
            </li>
            @endforeach
        </ul>
        <a class="posts__all" href="posts.html">Смотреть все</a>
    </div>
</section>
@endif
