<?php
	
	
//Actionの処理

$article = $articleRepository->find($id);

$domainOutput = [
    ‘id’ => $id,
    ‘article’ => $article,
];

$responder = new Responder();
$httpResponse = $responder($request, new HttpResponse(), $domainOutput);


//Responderクラス
class Responder {

    public function __invoke($request, $response, array $domainOutput)
    {
        $article = $domainOutput[‘article’];
        
        //↓nullであるという情報からオブジェクトが見つからなかったという結果を解釈している
        if($article === null) {
             $content = $this->templateRenderer->render(‘not_found’, [‘id’ => $id]);
             return $response
                 ->setStatus(404)
                 ->setContent($content);
        }

        $content = $this->templateRenderer->render(‘show’, [‘article’ => $article]);
        return $response
            ->setStatus(200)
            ->setContent($content);        
    }
}