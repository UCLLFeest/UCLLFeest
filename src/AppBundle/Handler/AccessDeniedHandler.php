<?php

namespace AppBundle\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use \Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * Handles access denied exceptions and redirects the user to the homepage.
 * @package AppBundle\Handler
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{

	/**
	 * Handles an access denied failure.
	 *
	 * @param Request $request
	 * @param AccessDeniedException $accessDeniedException
	 * @return Response
	 */
	public function handle(Request $request, AccessDeniedException $accessDeniedException)
	{
		return new RedirectResponse('/');
	}
}