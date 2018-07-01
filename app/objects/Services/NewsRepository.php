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

    public function findById($id): ?News{
        $query = <<<SQL
        select id, title, content, date_time, date_time, created_by
        from news
        where id=:id;
SQL;
        $query = $this->baza->prepare($query);
        $query->execute([':id' => $id]);
        $query = $query->fetch();
        return $query ? new News($query['title'], $query['content'], $query['created_by'], $query['date_time'], $query['id']) : null;
    }

    public function findNews(): array{
        $query = <<<SQL
        select news.id, news.title, news.content, DATE_FORMAT(news.date_time, "%d.%m.%Y %H:%i") date_time, users.username
        from news left join users on news.created_by=users.id
        order by news.date_time DESC;
SQL;
        $query = $this->baza->query($query);
        return $query->fetchAll() ?: [];
    }

    public function deleteById($id){
        $query = <<<SQL
        delete from news
        where id=:id;
SQL;

        $query = $this->baza->prepare($query);
        $query->execute([':id' => $id]);
    }

    public function updateNews(News $news): void{
        $query = <<<SQL
        update news
        set title=:title, content=:content, date_time=default
        where id=:id;
SQL;
        $query = $this->baza->prepare($query);
        $query->execute([':id' => $news->id(), ':title' => $news->title(), ':content' => $news->content()]);
    }
}