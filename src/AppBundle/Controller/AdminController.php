<?php

/**
 * Class AdminController
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the main admin area.
 * @package AppBundle\Controller
 */
class AdminController extends Controller
{
	/**
	 * Displays the admin control panel.
	 * @Route("/admin", name="adminoverview")
	 * @return Response
	 */
	public function overview()
	{
		return $this->render('admin/overview.html.twig');
	}
}