<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        // Crée un client HTTP
        $client = static::createClient();
        
        // Effectue la requête vers la page d'accueil
        $client->request('GET', '/');
        
        // Vérifie que la réponse est une redirection
        $this->assertResponseRedirects();
        
        // Vérifie la destination de la redirection
        $this->assertResponseRedirects('/products', 302);
        
        // Alternative : vérifie avec le nom de la route
        $this->assertResponseRedirects(
            $client->getContainer()->get('router')->generate('product_list')
        );
    }}