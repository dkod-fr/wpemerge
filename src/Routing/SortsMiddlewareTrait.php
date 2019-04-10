<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <atanas.angelov.dev@gmail.com>
 * @copyright 2018 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 */

namespace WPEmerge\Routing;

/**
 * Provide middleware sorting.
 */
trait SortsMiddlewareTrait {
	/**
	 * Middleware sorted in order of execution.
	 *
	 * @var array<string>
	 */
	protected $middleware_priority = [];

	/**
	 * Get middleware execution priority.
	 *
	 * @codeCoverageIgnore
	 * @return array<string>
	 */
	public function getMiddlewarePriority() {
		return $this->middleware_priority;
	}

	/**
	 * Set middleware execution priority.
	 *
	 * @codeCoverageIgnore
	 * @param  array<string> $middleware_priority
	 * @return void
	 */
	public function setMiddlewarePriority( $middleware_priority ) {
		$this->middleware_priority = $middleware_priority;
	}

	/**
	 * Get priority for a specific middleware.
	 * This is in reverse compared to definition order.
	 * Middleware with unspecified priority will yield -1.
	 *
	 * @param  string  $middleware
	 * @return integer
	 */
	public function getMiddlewarePriorityForMiddleware( $middleware ) {
		$increasing_priority = array_reverse( $this->middleware_priority );
		$priority = array_search( $middleware, $increasing_priority );
		return $priority !== false ? (int) $priority : -1;
	}

	/**
	 * Sort array of fully qualified middleware class names by priority in ascending order.
	 *
	 * @param  array<string> $middleware
	 * @return array
	 */
	public function sortMiddleware( $middleware ) {
		$sorted = $middleware;

		usort( $sorted, function ( $a, $b ) use ( $middleware ) {
			$priority = $this->getMiddlewarePriorityForMiddleware( $b ) - $this->getMiddlewarePriorityForMiddleware( $a );

			if ( $priority !== 0 ) {
				return $priority;
			}

			// Keep relative order from original array.
			return array_search( $a, $middleware ) - array_search( $b, $middleware );
		} );

		return array_values( $sorted );
	}
}
