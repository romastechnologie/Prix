<?php

namespace App\Controller;


use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Environment;
use App\Entity\Produit;
use App\Entity\Transfert;
use App\Database\NativeQueryMySQL;
use App\Entity\ProduitConditionnement;
use App\Services\LibrairieService;
use App\Repository\FamilleRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConditionnementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ProduitConditionnementRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gprix/stat')]
class HistorisationController extends AbstractController
{

    protected $columnsDefault = [];
    protected $em;
    protected $passwordHasher;
    protected $tokenManager;
    public function __construct(
        EntityManagerInterface $em,
        CsrfTokenManagerInterface $tokenManager
    )
    {
        $this->em = $em;
        $this->tokenManager = $tokenManager;
    }

    #[Route('/historisation', name: 'app_historisation')]
    public function index(): Response
    {
        return $this->render('historisation/index.html.twig', [
            'controller_name' => 'HistorisationController',
        ]);
    }

      /**
     * @param Request $request
     * @param $class
     * @return void
     * @Route("/{class}-data-load/ajax", name="app_load_data_ajax", methods={"GET", "POST"})
     */
    public function ajaxLoad(Request $request, NativeQueryMySQL $native, LibrairieService $lib, $class) : Response
    {
        $parameters = $request->getMethod() == 'GET' ? $request->query->all() : $request->request->all();
        $result = null;
        if(isset($parameters['id']) && !empty($parameters['id']))
        {
            $id = $parameters['id'];
            $token = $this->renderView('_token.html.twig',['id'=> $id]);
            $result = [
                'id'    => $id,
                'token' => $token,
            ];
        }
        else
        {
            $classname = 'App\\Entity\\'.ucfirst($class);
            $default_alias = "o";
            $list_columns_result = "";
            $list_join_columns_result = "";
            $join_alias = [];
            $join_sql = "";
            $sql = "SELECT :columns \nFROM $classname $default_alias ";
            $count_sql = "SELECT COUNT($default_alias) \nFROM $classname $default_alias ";
            $search = (isset($parameters['search']['value'])
                && $parameters['search']['value']) ? $parameters['search']['value'] : '';
            $where = "\nWHERE ";
            $cpt = 0;
            //Gestion du chargement par ajax
            if (isset($parameters['champs']) && is_array($parameters['champs'])) {
                foreach ($parameters['champs'] as $key => $field) {
                    //Chercher la position de la sous chaîne ":$class" dans la colonne $field
                    $position = strpos($field, ":");
                    if($position === false)
                    {
                        $list_columns_result .= $default_alias . ".$field, ";
                        $where .= $default_alias . ".$field LIKE '%$search%' OR ";
                        $this->columnsDefault[$field] = true;
                    }
                    else
                    {
                        $cpt++;
                        //Récupérer la position du # dans la colonne
                        $diese_index = strpos($field, "#");
                        //Récupérer la colonne réelle de la table de jointure
                        $champs = explode('#', substr($field, $diese_index+1, strlen($field)));
                        //Récupérer le nom de la colonne de jointure
                        $join_entity_name = substr($field, 0, $position);
                        $join_table_name = substr($field, $position+1,$diese_index - $position - 1);
                        //Récupérer le nom équivalent dans la base de donnée
                        $database_table_name = $lib->camel2dashed($join_table_name);
                        //Récupérer toutes les colonnes de la table jointure $database_table_name
                        $all_cols = $native::GetAllColumnsFromTable($database_table_name);
                        //Récupérer celles qui ne sont ni clé primaire, ni étrangère
                        $h = 0;
                        $concat = ""; $alias = "";
                        foreach ($all_cols as $col) {
                            if($col['Key'] === "")
                            {
                                $assoc_col = lcfirst($lib->camelize($col['Field']));
                                if(property_exists('App\\Entity\\'.ucfirst($join_table_name), $assoc_col) && in_array($assoc_col, $champs))
                                {
                                    $h++;
                                    //if($h === 1) $concat .= "CONCAT(";
                                    $concat .= $default_alias . '_' . $cpt . ".$assoc_col, ' ', ";
                                    $alias .= strtolower($assoc_col) . "_";
                                }
                            }
                        }
                        $alias .= $cpt;
                        /*$concat = rtrim($concat, ", ' ' ");*/
                        $tab = explode(',', rtrim($concat, ", ' ' "));
                        if(sizeof($tab) > 1)
                        {
                            $concat = substr_replace(rtrim($concat, ", "), "CONCAT(", 0, 0);
                            $concat = substr_replace($concat, ")", strlen($concat), 0);
                        }
                        else
                            $concat = rtrim($concat, ", ' ' ");

                        /*$concat = rtrim($concat, ", ") . ")";*/
                        $this->columnsDefault[$alias] = true;
                        $list_join_columns_result .= $concat . " AS $alias, ";
                        $where .= "$concat LIKE '%$search%' OR ";
                        //Mettre à jour la chaîne de jointure
                        $join_sql .= "\nINNER JOIN " . $default_alias . "." . $join_entity_name . " " . $default_alias . '_' . $cpt;
                    }
                }
                $list_columns_result .= $list_join_columns_result;
            }
            $html = $this->renderView('_actions.html.twig',['id'=> '|o.id|','class' => $class]);
            $vs = explode('|', $html);
            $gs = "";
            foreach ($vs as $key => $v)
            {
                if($key === 0) $gs .= "CONCAT(";
                $gs .= strpos($v, "o.id") === false ? "'$v', " : $v . ', ';
            }
            $gs = rtrim($gs, ", ");
            $gs .= ")";
            $list_columns_result .= $gs . " AS action";
            $list_columns_result = rtrim($list_columns_result, ", ");
            if(strlen($where) > 6)
                $where = rtrim($where, " OR ");
            else
                $where = "";
            //Reconstruire la requête avec les jointures
            $sql .= $join_sql;
            $count_sql .= $join_sql;
            $sql = str_replace(":columns", $list_columns_result, $sql) . $where;
            $count_sql .= $where;

            //Récupération des données
            $alldata = $this->em->createQuery($sql)
                ->setMaxResults($parameters['length'])
                ->setFirstResult($parameters['start'])
                ->getArrayResult();
            /*dd($alldata);*/
            // compter les données
            $totalRecords = $totalDisplay = $this->em->createQuery($count_sql)->getSingleScalarResult();
            $data = $this->reformat($alldata);

            $result = [
                'recordsTotal'    => $totalRecords,
                'recordsFiltered' => $totalDisplay,
                'data'            => $data,
            ];
        }
        //dd($parameters);
        //Retourner la réponse finale sous forme json
        return new JsonResponse($result);
    }

}
