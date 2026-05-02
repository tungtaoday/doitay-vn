export default function Loading() {
  return (
    <div className="space-y-8">
      <div className="h-9 w-40 animate-pulse rounded-xl bg-surface-container" />

      {/* Balance cards skeleton */}
      <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
        {Array.from({ length: 4 }).map((_, i) => (
          <div key={i} className="animate-pulse rounded-2xl bg-surface-container-lowest p-5">
            <div className="h-3 w-20 rounded bg-surface-container" />
            <div className="mt-2 h-8 w-28 rounded bg-surface-container" />
          </div>
        ))}
      </div>

      {/* Transactions skeleton */}
      <div>
        <div className="mb-4 h-6 w-40 animate-pulse rounded bg-surface-container" />
        <div className="space-y-3">
          {Array.from({ length: 5 }).map((_, i) => (
            <div key={i} className="animate-pulse rounded-xl bg-surface-container-lowest p-4">
              <div className="flex items-center justify-between">
                <div className="space-y-1.5">
                  <div className="h-4 w-36 rounded bg-surface-container" />
                  <div className="h-3 w-24 rounded bg-surface-container" />
                </div>
                <div className="h-5 w-20 rounded bg-surface-container" />
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
