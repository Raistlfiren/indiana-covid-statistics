<?php


namespace App\Service;


class INDataHubPuller
{
    const IN_DATA_HUB_COVID_URL = 'https://hub.mph.in.gov/api/3/action/package_search?fq=tags:COVID';

    public function pull()
    {
        $date = new \DateTime();
        $covidRepositories = json_decode(file_get_contents(self::IN_DATA_HUB_COVID_URL));

        if ($covidRepositories && $covidRepositories->result) {

            foreach ($covidRepositories->result->results as $covidRepository) {
                if ($covidRepository->resources) {
                    foreach ($covidRepository->resources as $dataSource) {
                        //Ignore Mapping extras and data dictionaries
                        if (! preg_match('/mapping|dictionary/i', $dataSource->name)) {
                            $id = $dataSource->id;
                            //Clean up name for directory
                            $name = trim(mb_ereg_replace("([^\w\s\d\-_\[\]\(\).])", '', $dataSource->name));
                            $name = mb_ereg_replace("([\.]{2,})", '', $name);
                            $name = str_replace(' ', '-', $name);

                            //Generate URL to get data
                            $url = 'https://hub.mph.in.gov/datastore/dump/' . $id . '?bom=True';
                            $data = @file_get_contents($url);

                            $filePath = __DIR__ . '/../../Resources/hub.mph.in.gov/' . $name . '/';

                            //Check to see if file exists
                            if (file_exists($filePath)) {
                                file_put_contents($filePath . $date->format('m-d-y') . '.csv', $data);
                            } else {
                                if (mkdir($filePath)) {
                                    file_put_contents($filePath . $date->format('m-d-y') . '.csv', $data);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}