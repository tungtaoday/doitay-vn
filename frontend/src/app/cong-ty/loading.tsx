export default function Loading() {
  return (
    <div className="mx-auto max-w-7xl px-4 py-10">
      {/* Search bar skeleton */}
      <div className="mb-8 h-12 w-full max-w-md animate-pulse rounded-xl bg-surface-container" />

      {/* Grid skeleton */}
      <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        {Array.from({ length: 6 }).map((_, i) => (
          <div
            key={i}
            className="animate-pulse rounded-2xl border border-outline-variant/20 bg-surface-container-low p-5"
          >
            <div className="mb-3 flex items-center gap-3">
              <div className="h-12 w-12 rounded-full bg-surface-container" />
              <div className="flex-1 space-y-2">
                <div className="h-4 w-3/4 rounded bg-surface-container" />
                <div className="h-3 w-1/2 rounded bg-surface-container" />
              </div>
            </div>
            <div className="space-y-2">
              <div className="h-3 w-full rounded bg-surface-container" />
              <div className="h-3 w-2/3 rounded bg-surface-container" />
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
