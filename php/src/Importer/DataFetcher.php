<?php

namespace App\Importer;

use App\DTO\MemberListData;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Exception;
use Iterator;
use DOMElement;

class DataFetcher
{
    private const MEP_LIST_URL = 'https://www.europarl.europa.eu/meps/en/full-list/xml/a';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private $crawler = new Crawler(),
    ) {
    }

    /**
     * @return Iterator|MemberListData[]
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws Exception
     */
    public function getMemberListIterator(): Iterator
    {
        $response = $this->httpClient->request(
            Request::METHOD_GET,
            self::MEP_LIST_URL,
        );

        if ($response->getStatusCode() !== 200) {
            throw new Exception(
                'Error Response: %s, %s',
                $response->getStatusCode(),
                $response->getContent(),
            );
        }

        $this->crawler->add($response->getContent());

        $memberNodes = $this->crawler->filter('meps > mep');
        foreach ($memberNodes as $memberNode) {
            $memberData = new MemberListData();
            foreach ($memberNode->childNodes as $childNode) {
                /** @var $childNode DOMElement */



                if (property_exists($memberData, $childNode->tagName)) {
                    $memberData->{$childNode->tagName} = $childNode->textContent;
                }
            }

            yield $memberData;
        }
    }
}