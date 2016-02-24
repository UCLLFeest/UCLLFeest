<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 2/23/2016
 * Time: 3:31 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AdminController extends Controller
{
	/**
	 * @Route("/admin", name="adminoverview")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function overview()
	{
		return $this->render('admin/overview.html.twig');
	}
}