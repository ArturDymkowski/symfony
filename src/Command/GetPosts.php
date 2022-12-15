<?php

namespace App\Command;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bridge\Monolog\Logger;

#[AsCommand(
    name: 'api:get-posts',
    description: 'Get posts from API',
    aliases: ['api:get-posts'],
    hidden: false
)]

class GetPosts extends Command
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        parent::__construct();
        $this->postRepository = $postRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new \Symfony\Component\HttpClient\CurlHttpClient();

        $response = $client->request(
            'GET',
            'https://jsonplaceholder.typicode.com/posts'
        );

        $post_content = $response->toArray();
        $progressBar = new ProgressBar($output, count($post_content));

        if (!empty($post_content)) {
            $progressBar->start();
            foreach ($post_content as $content) {

                if (!$this->postRepository->findOneBy(['id' => $content['id']])) {
                    $response = $client->request(
                        'GET',
                        'https://jsonplaceholder.typicode.com/users/' . $content['userId']
                    );

                    $user_content = $response->toArray();

                    $post = new Post();
                    $post->setId($content['id']);
                    $post->setTitle($content['title']);
                    $post->setBody($content['body']);
                    $post->setAuthorName($user_content['name']);
                    $post->setAuthorSurname($user_content['username']);

                    $this->postRepository->save($post, true);
                    $progressBar->advance();
                }
            }
        }

        $progressBar->finish();
        return Command::SUCCESS;
    }
}