<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ObjectManager;
use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
use League\Bundle\OAuth2ServerBundle\Manager\Doctrine\ClientManager;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use League\Bundle\OAuth2ServerBundle\ValueObject\Grant;

class OAuthClientFixtures extends AbstractFixture implements ORMFixtureInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, private ClientManagerInterface $clientManager)
    {
        $this->em = $em;
        $this->em->beginTransaction();
    }

    public function load(ObjectManager $manager): void
    {
        $schemaTool = new SchemaTool($this->em);
        $classe = $this->em->getClassMetadata(Client::class);
        try {
            $schemaTool->dropSchema([$classe]);
            $schemaTool->createSchema([$classe]);
        } catch (\Exception) {

        }

        $client = new Client('gauthier', '67d77d83865664a264570e88dcf8644a', '2d993d98d443ea363059a1948aae5b3336856405008f7a694192c6c1560286ab628862094f9c88ef65ad594bce6d6e125cb4d7b698a3e58b9d0975cc86426684');
        $client->setGrants(new Grant('password'));

        $manager->persist($client);
        $manager->flush();
        $this->clientManager->save($client);
    }
}
