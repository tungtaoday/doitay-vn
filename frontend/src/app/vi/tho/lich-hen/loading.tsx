export default function Loading() {
  return (
    <div>
      <div className="mb-4 h-9 w-64 animate-pulse rounded-xl bg-surface-container" />

      {/* Stats skeleton */}
      <div className="mb-8 grid grid-cols-2 gap-4 md:grid-cols-4">
        {Array.from({ length: 4 }).map((_, i) => (
          <div key={i} className="animate-pulse rounded-2xl bg-surface-container-lowest p-5">
            <div className="h-3 w-20 rounded bg-surface-container" />
            <div className="mt-2 h-7 w-16 rounded bg-surface-container" />
          </div>
        ))}
      </div>

      {/* Filter chips skeleton */}
      <div className="mb-6 flex gap-2">
        {Array.from({ length: 5 }).map((_, i) => (
          <div key={i} className="h-8 w-24 animate-pulse rounded-full bg-surface-container" />
        ))}
      </div>

      {/* List skeleton */}
      <div className="space-y-4">
        {Array.from({ length: 4 }).map((_, i) => (
          <div key={i} className="animate-pulse rounded-2xl bg-surface-container-lowest p-6">
            <div className="flex items-center justify-between">
              <div className="space-y-2">
                <div className="h-5 w-36 rounded bg-surface-container" />
                <div className="h-4 w-32 rounded bg-surface-container" />
                <div className="h-4 w-24 rounded bg-surface-container" />
              </div>
              <div className="flex flex-col items-end gap-2">
                <div className="h-6 w-28 rounded-full bg-surface-container" />
                <div className="h-3 w-20 rounded bg-surface-container" />
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
