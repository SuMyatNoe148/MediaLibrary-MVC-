<?php

namespace MediaLibrary\Presentation\Views;

class ItemView
{
    public static function render(array $item): string
    {
        
        $id    = htmlspecialchars($item['media_id'], ENT_QUOTES, 'UTF-8'); // or 'id' if DB says so
        $img   = htmlspecialchars($item['img'], ENT_QUOTES, 'UTF-8');
        $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');

        return "
        <li>
            <a href='index.php?page=details&id={$id}'>
                <img src='{$img}' alt='{$title}' />
                <p>View Details</p>
            </a>
        </li>";
    }
}
