<?php
namespace Internit\AcompanhamentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tupi\ContentBundle\Entity\ImageMedia;
use Internit\AcompanhamentoBundle\Form\UploadHelper;
use Symfony\Component\HttpFoundation\Request;

class ImagemController extends Controller
{
    public function indexAction($bloco, $filter)
    {
        $galeria = $this->getDoctrine()->getRepository(GaleriaController::REPOSITORY_NAME)->find($filter);
        return $this->render('InternitAcompanhamentoBundle:Imagem:cadastro.html.twig', array(
            "images" => $galeria->getImages(),
            "filter" => $filter,
            "bloco" => $bloco
        ));
    }

    public function newAction($filter)
    {
        $em = $this->getDoctrine()->getManager();
    
        $file = $this->getRequest()->files->get('file');
        $upload = new UploadHelper(new ImageMedia(), $file);
    
        if($upload->doUpload() && $upload->isImage())
        {
            $upload->resizeCropImage(136, 120, true);
            /*
             * Implementado sistema para cortar a imagem do tamanho correto na galeria do acompanhamento
             * Para a segunda imagem, passar o ultimo parametro como true
             * No banco de dados o campo é thumb_crop
             * */
            $upload->resizeCropImage(1230, 470, true, true);
            
        }
    
        $image = $upload->getMedia();
        
        $galeria = $this->getDoctrine()->getRepository(GaleriaController::REPOSITORY_NAME)->find($filter);
        $galeria->addImage($image);
        
        $em->persist($galeria);
        $em->flush();
        
        return $this->render('InternitAcompanhamentoBundle:Imagem:image.html.twig', array(
            "image" => $image,
            "filter" => $filter
        ));
    }
    
    public function editAction(Request $request, $id, $filter)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $this->getDoctrine()->getRepository("TupiContentBundle:ImageMedia")->find($id);
        $image->setLabel($request->request->get('legenda'));
        $em->merge($image);
        $em->flush();
        
        $return = new ReturnJson();
        $return->setMessage("Legenda alterada com sucesso!");

        return new JsonResponse($return->getJson());
    }
    
    public function removeAction($id, $filter)
    {
        $em = $this->getDoctrine()->getManager();
        $galeria = $this->getDoctrine()->getRepository(GaleriaController::REPOSITORY_NAME)->find($filter);
        $image = $this->getDoctrine()->getRepository("TupiContentBundle:ImageMedia")->find($id);
        
        $galeria->removeImage($image);
        $em->remove($image);
        $em->flush();
        
        $return = new ReturnJson();
        $return->setMessage("Imagem deletada com sucesso!");

        return new JsonResponse($return->getJson());
    }
    
}