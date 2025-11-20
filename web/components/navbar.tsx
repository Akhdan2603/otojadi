'use client';

import Link from 'next/link';
import { useSession, signOut } from 'next-auth/react';
import { Menu, Search, ShoppingCart, User, LogOut, LayoutDashboard } from 'lucide-react';
import { useState } from 'react';

export default function Navbar() {
  const { data: session } = useSession();
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  return (
    <nav className="bg-white border-b sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16">
          <div className="flex items-center">
            <Link href="/" className="flex-shrink-0 flex items-center">
              <span className="text-2xl font-bold text-blue-600">otojadi</span>
            </Link>
            <div className="hidden md:ml-6 md:flex md:space-x-8">
              <Link href="/products" className="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                Templates
              </Link>
              <Link href="/categories" className="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                Categories
              </Link>
            </div>
          </div>

          <div className="flex-1 flex items-center justify-center px-2 lg:ml-6 lg:justify-end">
            <div className="max-w-lg w-full lg:max-w-xs">
              <label htmlFor="search" className="sr-only">Search</label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <Search className="h-5 w-5 text-gray-400" aria-hidden="true" />
                </div>
                <input
                  id="search"
                  name="search"
                  className="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  placeholder="Search templates..."
                  type="search"
                />
              </div>
            </div>
          </div>

          <div className="flex items-center lg:ml-4">
            <Link href="/cart" className="p-2 text-gray-400 hover:text-gray-500 relative">
              <span className="sr-only">Cart</span>
              <ShoppingCart className="h-6 w-6" aria-hidden="true" />
              {/* Cart count badge logic here */}
            </Link>

            <div className="ml-3 relative">
              {session ? (
                <div className="flex items-center space-x-3">
                   <Link href="/dashboard" className="hidden md:block text-sm font-medium text-gray-700 hover:text-blue-600">
                      Dashboard
                   </Link>
                   {session.user.role === 'ADMIN' && (
                       <Link href="/admin" className="hidden md:block text-sm font-medium text-red-600 hover:text-red-800">
                          Admin
                       </Link>
                   )}
                   <button onClick={() => signOut()} className="p-1 rounded-full text-gray-400 hover:text-gray-500">
                      <LogOut className="h-6 w-6" />
                   </button>
                </div>
              ) : (
                <div className="flex items-center space-x-4">
                  <Link href="/login" className="text-sm font-medium text-gray-700 hover:text-gray-900">Log in</Link>
                  <Link href="/register" className="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                    Sign up
                  </Link>
                </div>
              )}
            </div>

             {/* Mobile menu button */}
             <div className="flex items-center md:hidden ml-2">
                <button
                  onClick={() => setIsMenuOpen(!isMenuOpen)}
                  className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                >
                  <Menu className="block h-6 w-6" />
                </button>
             </div>
          </div>
        </div>
      </div>

      {/* Mobile Menu */}
      {isMenuOpen && (
        <div className="md:hidden">
          <div className="pt-2 pb-3 space-y-1">
            <Link href="/products" className="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
              Templates
            </Link>
            <Link href="/categories" className="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
              Categories
            </Link>
            {session && (
                <>
                    <Link href="/dashboard" className="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Dashboard</Link>
                    {session.user.role === 'ADMIN' && (
                        <Link href="/admin" className="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-gray-50">Admin Panel</Link>
                    )}
                </>
            )}
          </div>
        </div>
      )}
    </nav>
  );
}
