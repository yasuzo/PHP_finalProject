<?php

namespace Http\Responses;

class AttachmentResponse implements Response {
    private $fileName;
    private $content;
    private $contentType;

    public function __construct(string $fileName, string $content, string $contentType){
        $this->fileName = $fileName;
        $this->content = $content;
        $this->contentType = $contentType;
    }

    public function send(): void{
        header("Content-Type: " . $this->contentType);
        header("Content-Disposition: attachment; filename='$this->fileName'");
        echo $this->content;
    }
}