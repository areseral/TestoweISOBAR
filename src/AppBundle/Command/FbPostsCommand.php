<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Facebook\FacebookSession;

use AppBundle\Entity\FBposts;

class FbPostsCommand extends ContainerAwareCommand 
{
    
    private $em;
    private $connection;
    
    protected function configure()
    {
        $this->setName('app:fb-posts')
                ->setDescription('Get last 50 FB posts')
                ->setHelp('Command recieve last 50 FB posts');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        try {
            
            $fb = new \Facebook\Facebook([
                'app_id' => '1724810674475686',
                'app_secret' => 'e96096eb8a0d41ae1063c2988be703c8',
                'default_graph_version' => 'v2.8',
            ]);

            $accessToken = 'EAAYgtMeOkqYBAMA0OZBjZCVwkjFRPcyZB1c1anGgsMMd39StwAQTUcRDjxXebQRQqu3HLE9JDeERzyB8fl4i1jMvZBDBzDg1jIfe4hRmXHPzlJoBZCorCMJsUbnEKZChfGqzVcGkTZAk5LaNiVQ3RLhpZCskjO8XnXYZD';

            $oAuth2Client = $fb->getOAuth2Client();

            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken( (string) $accessToken );

            $fb->setDefaultAccessToken( $longLivedAccessToken );

            $posts = $fb->get('/188351153004/feed?fields=id,message,created_time,from&limit=50');

            $graphEdge = $posts->getGraphEdge(); 

            $em = $this->getContainer()->get('doctrine')->getEntityManager();

            $productRepository = $em->getRepository('AppBundle:FBposts');
            
            $i = 0;

            foreach( $graphEdge as $graphNode )
            {

                if( ! $productRepository->checkIsExistPostsFB( $graphNode['id'] ) )
                {
                    $post = new FBposts();
                    $post->setIdPost( $graphNode['id'] );
                    $post->setMessage( $graphNode['message'] );
                    $post->setFromId( $graphNode['from']['id'] );
                    $post->setFromName( $graphNode['from']['name'] );
                    $post->setCreated( $graphNode['created_time'] );

                    $em->persist($post);

                    $em->flush();
                    
                    $i++;
                }

            }
            
            echo "Receive $i newest post from FB finished!";

        }
        catch(Exception $e){
            echo "Error! " . $e->getMessage();
        }
        
    }
}
