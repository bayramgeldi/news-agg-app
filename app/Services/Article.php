<?php

namespace App\Services;

use App\Models\Category;

class Article
{
    private string $source;

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param  string  $source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    /**
     * @return array
     */
    public function getSubSource(): array
    {
        return $this->subSource;
    }

    /**
     * @param  array  $subSource
     */
    public function setSubSource(array $subSource): void
    {
        $this->subSource = $subSource;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param  string  $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param  string  $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param  string  $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrlToImage(): string
    {
        return $this->urlToImage;
    }

    /**
     * @param  string  $urlToImage
     */
    public function setUrlToImage(string $urlToImage): void
    {
        $this->urlToImage = $urlToImage;
    }

    /**
     * @return string
     */
    public function getPublishedAt(): string
    {
        return $this->publishedAt;
    }

    /**
     * @param  string  $publishedAt
     */
    public function setPublishedAt(string $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param  string  $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    protected array $subSource;
    protected string $author;
    protected string $title;
    protected Category $category;
    protected string $description;
    protected string $url;
    protected string $urlToImage;
    protected string $publishedAt;
    protected string $content;

    /**
     * @param  string  $source
     * @param  array  $subSource
     * @param  string  $author
     * @param  string  $title
     * @param  Category  $category
     * @param  string  $description
     * @param  string  $url
     * @param  string  $urlToImage
     * @param  string  $publishedAt
     * @param  string  $content
     */
    public function __construct(
        string $source,
        array $subSource,
        string $author,
        string $title,
        Category $category,
        string $description,
        string $url,
        string $urlToImage,
        string $publishedAt,
        string $content
    ) {
        $this->source = $source;
        $this->subSource = $subSource;
        $this->author = $author;
        $this->title = $title;
        $this->category = $category;
        $this->description = $description;
        $this->url = $url;
        $this->urlToImage = $urlToImage;
        $this->publishedAt = $publishedAt;
        $this->content = $content;
    }


    public function collect(): object
    {
        //return object of article
        return (object) [
            'source' => $this->source,
            'subSource' => $this->subSource,
            'author' => $this->author,
            'title' => $this->title,
            'category' => $this->category,
            'description' => $this->description,
            'url' => $this->url,
            'urlToImage' => $this->urlToImage,
            'publishedAt' => $this->publishedAt,
            'content' => $this->content,
        ];
    }


}
