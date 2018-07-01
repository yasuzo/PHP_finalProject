<?php

namespace Services;

use Models\News;

class NewsRepository{
    private $baza;

    public function __construct(\PDO $baza){
        $this->baza = $baza;
    }

    public function persist(News $news): void{
        $result = [];

        $query = <<<SQL
        insert into news
        (title, content, created_by) values
        (:title, :content, :created_by)
SQL;
        $query = $this->baza->prepare($query);
        $query->execute([':title' => $news->title(), ':content' => $news->content(), ':created_by' => $news->creator()]);
    }

    public function findNews(): array{
        $query = <<<SQL
        select news.title, news.content, DATE_FORMAT(news.date_time, "%d.%m.%Y %H:%i") date_time, users.username
        from news left join users on news.created_by=users.id
        order by news.date_time DESC;
SQL;
        $query = $this->baza->query($query);
        return $query->fetchAll() ?: [];
    }
}