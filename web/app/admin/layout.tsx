import Link from 'next/link';
import { Users, Package, ShoppingBag, BarChart } from 'lucide-react';

export default function AdminLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="flex h-screen bg-gray-100">
      {/* Sidebar */}
      <aside className="w-64 bg-white shadow-md hidden md:block">
        <div className="p-6">
          <h1 className="text-2xl font-bold text-blue-600">Otojadi Admin</h1>
        </div>
        <nav className="mt-6">
          <Link href="/admin" className="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <BarChart className="h-5 w-5 mr-3" />
            Dashboard
          </Link>
          <Link href="/admin/products" className="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <Package className="h-5 w-5 mr-3" />
            Products
          </Link>
          <Link href="/admin/orders" className="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <ShoppingBag className="h-5 w-5 mr-3" />
            Orders
          </Link>
          <Link href="/admin/users" className="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600">
            <Users className="h-5 w-5 mr-3" />
            Users
          </Link>
        </nav>
      </aside>

      {/* Main Content */}
      <main className="flex-1 overflow-y-auto p-8">
        {children}
      </main>
    </div>
  );
}
