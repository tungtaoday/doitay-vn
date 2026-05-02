export default function Loading() {
  return (
    <div className="space-y-8">
      <div className="h-9 w-36 animate-pulse rounded-xl bg-surface-container" />

      <div className="animate-pulse rounded-2xl bg-surface-container-lowest p-8">
        <div className="space-y-6">
          {/* Wallet select skeleton */}
          <div>
            <div className="mb-2 h-4 w-20 rounded bg-surface-container" />
            <div className="h-14 w-full rounded-xl bg-surface-container" />
          </div>

          {/* Method select skeleton */}
          <div>
            <div className="mb-2 h-4 w-32 rounded bg-surface-container" />
            <div className="h-14 w-full rounded-xl bg-surface-container" />
          </div>

          {/* Amount presets skeleton */}
          <div>
            <div className="mb-2 h-4 w-24 rounded bg-surface-container" />
            <div className="grid grid-cols-4 gap-3">
              {Array.from({ length: 4 }).map((_, i) => (
                <div key={i} className="h-12 rounded-xl bg-surface-container" />
              ))}
            </div>
          </div>

          {/* Amount input skeleton */}
          <div className="h-14 w-full rounded-xl bg-surface-container" />

          {/* Submit skeleton */}
          <div className="h-14 w-full rounded-xl bg-surface-container" />
        </div>
      </div>
    </div>
  );
}
