export default function Loading() {
  return (
    <div>
      <div className="mb-8 h-9 w-52 animate-pulse rounded-xl bg-surface-container" />
      <div className="space-y-4">
        {Array.from({ length: 4 }).map((_, i) => (
          <div
            key={i}
            className="animate-pulse rounded-2xl bg-surface-container-lowest p-6"
          >
            <div className="flex items-center justify-between">
              <div className="space-y-2">
                <div className="h-5 w-40 rounded bg-surface-container" />
                <div className="h-4 w-32 rounded bg-surface-container" />
                <div className="h-4 w-48 rounded bg-surface-container" />
              </div>
              <div className="flex flex-col items-end gap-2">
                <div className="h-6 w-24 rounded-full bg-surface-container" />
                <div className="h-3 w-20 rounded bg-surface-container" />
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
