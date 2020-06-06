<?php

namespace App\Form;

use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Service\DataMaker;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;

class SelectorType extends AbstractType
{
    private $seasonRepository;
    private $episodeRepository;
    private $programRepository;

    public function __construct(SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository, ProgramRepository $programRepository)
    {
        $this->seasonRepository = $seasonRepository;
        $this->episodeRepository = $episodeRepository;
        $this->programRepository = $programRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $post = file_get_contents('php://input');
        $data = DataMaker::makeDataFromPost($post);
        $builder->add('Program',ChoiceType::class, [
                'choices' => $this->getProgramChoices(),
            ]);

        if (isset($data->Program)) {
            $builder->add('Season', ChoiceType::class, [
                'choices' => $this->getSeasonChoices($data->Program),
            ]);
        }
        if (isset($data->Season)) {
            $builder->add('Episode', ChoiceType::class, [
                'choices' => $this->getEpisodesChoices($data->Season),
            ]);
        }
    }

    private function getProgramChoices()
    {
        $programs = $this->programRepository->findAll();
        $choices = [];
        $choices['Choisir une série'] = '';
        foreach ($programs as $program) {
            $choices[$program->getTitle()] = $program->getId();
        }
        return $choices;
    }

    private function getSeasonChoices($programId)
    {
        $seasons = $this->seasonRepository->findBy(['program' => $programId]);
        $choices = [];
        $choices['Choisir une saison'] = '';
        foreach ($seasons as $season) {
            $choices['Saison ' . $season->getNumber()] = $season->getId();
        }
        return $choices;
    }

    private function getEpisodesChoices($seasonId)
    {
        $episodes = $this->episodeRepository->findBy([
            'season' => $seasonId,
        ]);
        $choices = [];
        $choices['Choisir un épisode'] = '';
        foreach ($episodes as $episode) {
            $choices[$episode->getTitle()] = $episode->getId();
        }
        return $choices;
    }
}
