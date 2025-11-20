import Link from "next/link";

export default function Home() {
  return (
    <div>
      {/* Hero Section */}
      <div className="bg-blue-600">
        <div className="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
          <div className="text-center">
            <h1 className="text-4xl font-extrabold text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
              Professional PowerPoint Templates
            </h1>
            <p className="mt-4 max-w-2xl mx-auto text-xl text-blue-100">
              Save time and impress your audience with our premium, fully editable presentation templates.
            </p>
            <div className="mt-8 w-full max-w-sm mx-auto sm:max-w-none sm:flex sm:justify-center">
              <div className="space-y-4 sm:space-y-0 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-5">
                <Link
                  href="/products"
                  className="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-blue-700 bg-white hover:bg-blue-50 sm:px-8"
                >
                  Browse Templates
                </Link>
                <Link
                  href="/register"
                  className="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-800 bg-opacity-60 hover:bg-opacity-70 sm:px-8"
                >
                  Get Started
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Featured Section (Placeholder) */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
         <h2 className="text-2xl font-bold tracking-tight text-gray-900">Featured Templates</h2>
         <div className="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            {/* Product Card Placeholders */}
            {[1, 2, 3, 4].map((item) => (
              <div key={item} className="group relative bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <div className="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:aspect-none lg:h-40">
                   <div className="h-full w-full flex items-center justify-center text-gray-400">Preview Image</div>
                </div>
                <div className="mt-4 flex justify-between">
                  <div>
                    <h3 className="text-sm text-gray-700">
                      <a href="#">
                        <span aria-hidden="true" className="absolute inset-0" />
                        Business Strategy Deck
                      </a>
                    </h3>
                    <p className="mt-1 text-sm text-gray-500">Business</p>
                  </div>
                  <p className="text-sm font-medium text-gray-900">$15.00</p>
                </div>
              </div>
            ))}
         </div>
      </div>
    </div>
  );
}
