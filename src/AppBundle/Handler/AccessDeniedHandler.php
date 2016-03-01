<?php

namespace AppBundle\Handler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use \Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{

	/**
	 * Handles an access denied failure.
	 *
	 * @param Request $request
	 * @param AccessDeniedException $accessDeniedException
	 *
	 * @return Response may return null
	 */
	public function handle(Request $request, AccessDeniedException $accessDeniedException)
	{
		return new RedirectResponse('/');
	}
}