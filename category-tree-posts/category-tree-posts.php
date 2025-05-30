<?php
/**
 * Plugin Name: Simple Category Tree with Posts (WP_Query version)
 * Description: Выводит дерево рубрик со статьями через шорткод [category_tree_posts], включая отладку.
 * Version: 1.1
 * Author: CTRLLIFE
 * Author URI: https://ctrllife.ru/
 */

function category_tree_posts_shortcode() {
    $categories = get_categories([
        'parent' => 0,
        'hide_empty' => true,
    ]);

    ob_start();
    echo '<!-- Plugin Loaded -->';
    
    echo '<ul class="category-tree">';
    foreach ($categories as $category) {
        $query = new WP_Query([
            'cat' => $category->term_id,
            'posts_per_page' => -1,
        ]);

        if ($query->have_posts()) {
            echo '<li class="category-item">';
            echo '<h3 class="toggle"><span class="arrow">&#9656;</span> ' . esc_html($category->name) . ' (' . $query->found_posts . ')</h3>';
            echo '<ul class="post-list" style="display:none">';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
            }
            echo '</ul>';
            echo '</li>';
            wp_reset_postdata();
        } else {
            echo '<!-- Нет постов в рубрике: ' . esc_html($category->name) . ' -->';
        }
    }
    echo '</ul>';
    ?>
    <style>
    .category-tree {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }
    .category-tree li {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .category-tree h3.toggle {
        font-size: 1rem;
        font-weight: 500;
        margin: 0;
        padding: 6px 8px;
        cursor: pointer;
        border-radius: 5px;
        transition: background 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .category-tree h3.toggle:hover {
        background: #f0f0f0;
    }
    .category-tree h3.toggle .arrow {
        display: inline-block;
        width: 1em;
        transition: transform 0.2s ease;
    }
    .category-tree h3.toggle.open .arrow {
        transform: rotate(90deg);
    }
    .category-tree .post-list {
        margin: 4px 0 8px 20px;
        padding: 0;
    }
    .category-tree .post-list li {
        padding: 3px 0;
    }
    .category-tree .post-list li a {
        text-decoration: none;
    }
</style>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".category-tree h3.toggle").forEach(el => {
            el.addEventListener("click", () => {
                let next = el.nextElementSibling;
                if (next && next.style) {
                    
                if (next.style.display === "none") {
                    next.style.display = "block";
                    el.classList.add("open");
                } else {
                    next.style.display = "none";
                    el.classList.remove("open");
                }
    
                }
            });
        });
    });
    </script>
    <?php

    return ob_get_clean();
}
add_shortcode('category_tree_posts', 'category_tree_posts_shortcode');
