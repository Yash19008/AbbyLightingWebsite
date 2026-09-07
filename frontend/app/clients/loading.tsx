export default function Loading() {
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header Section */}
      <div className="bg-white py-12 shadow-sm">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="h-10 w-64 animate-pulse rounded bg-gray-200"></div>
          <div className="mt-4 h-6 w-96 animate-pulse rounded bg-gray-200"></div>
        </div>
      </div>

      {/* Loading Grid */}
      <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div className="mb-6 h-5 w-32 animate-pulse rounded bg-gray-200"></div>
        <div className="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
          {[...Array(10)].map((_, i) => (
            <div
              key={i}
              className="aspect-square animate-pulse rounded-lg bg-gray-200"
            ></div>
          ))}
        </div>
      </div>
    </div>
  );
}
