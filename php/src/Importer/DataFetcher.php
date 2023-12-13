<?php

namespace App\Importer;

use App\DTO\MemberContactData;
use App\DTO\MemberListData;
use App\Entity\Member;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Exception;
use Iterator;
use DOMElement;

class DataFetcher
{
    private const MEP_LIST_URL = 'https://www.europarl.europa.eu/meps/en/full-list/xml/';
    private const MEP_CONTACT_INFO_URL_PLACEHOLDER = 'https://www.europarl.europa.eu/meps/en/%s/%s/home';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
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

        $crawler = new Crawler($response->getContent());
        $memberNodes = $crawler->filter('meps > mep');
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

    public function getMemberContactsIterator(Member $member): Iterator
    {
        $url = sprintf(
            self::MEP_CONTACT_INFO_URL_PLACEHOLDER,
            $member->getMepId(),
            urlencode(strtoupper($member->getFullName()))
        );

        $response = $this->httpClient->request(Request::METHOD_GET, $url);

        if ($response->getStatusCode() !== 200) {
            throw new Exception(
                'Error Response: %s, %s',
                $response->getStatusCode(),
                $response->getContent(),
            );
        }

        $crawler = new Crawler($response->getContent());
        $contactCardNodes = $crawler->filter('.erpl_contact-card');

        foreach ($contactCardNodes as $contactCardNode) {
            $cardCrawler = new Crawler($contactCardNode);
            $memberContactData = new MemberContactData();

            $addressNode = $cardCrawler->filter('.erpl_contact-card-list')->getNode(0);
            $phoneNumberNode = $cardCrawler->filter('a .t-x')->getNode(0);
            $city = trim($cardCrawler->filter('.erpl_title-h3')->innerText());

            if ($addressNode === null || $phoneNumberNode === null || $city === '') {
                /* @TODO log case */
                continue;
            }

            $memberContactData->city = $city;
            $memberContactData->address = trim($addressNode->textContent);
            $memberContactData->phoneNumber = trim($phoneNumberNode->textContent);

            yield $memberContactData;
        }
    }
}