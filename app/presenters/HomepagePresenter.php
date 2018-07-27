<?php

namespace App\Presenters;
use Nette;
use App\Model\ArticleManager;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
  private $articleManager;

  public function __construct(ArticleManager $articleManager )
  {
    $this->articleManager = $articleManager;
  }


public function renderDefault()
{
    $this->template->posts = $this->articleManager->getPublicArticles()->limit(5);
}


}
