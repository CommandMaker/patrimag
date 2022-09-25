import { useEffect } from 'react'

/**
 * useEffect for asynchronous functions
 * 
 * @param fn 
 * @param {Array} deps 
 */
const useAsyncEffect = (fn, deps = []) => {
    useEffect(() => {
        fn();
    }, deps);
}

export { useAsyncEffect };